document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) return;

    const modalClase = new bootstrap.Modal(document.getElementById('modalClase'));
    const infoEl = document.getElementById('infoClase');
    const motivoEl = document.getElementById('motivoCancelacion');
    const btnCancelar = document.getElementById('btnCancelarClase');

    const listaInscriptosEl = document.getElementById('listaInscriptos');
    const listaAsistenciaEl = document.getElementById('listaAsistencia');
    const contenedorAsistenciaEl = document.getElementById('contenedorAsistencia');
    const btnGuardarAsistencia = document.getElementById('btnGuardarAsistencia');

    const buscarInput = document.getElementById('buscarSocio');
    const btnBuscarSocio = document.getElementById('btnBuscarSocio');
    const resultadosSociosEl = document.getElementById('resultadosSocios');

    const asuntoEl = document.getElementById('correoAsunto');
    const mensajeEl = document.getElementById('correoMensaje');
    const btnEnviarCorreo = document.getElementById('btnEnviarCorreo');

    const toastEl = document.getElementById('toastSuccess');
    const toastMsg = document.getElementById('toastMsg');

    function mostrarToast(msg, error = false) {
        toastMsg.textContent = msg;
        toastEl.classList.toggle('text-bg-success', !error);
        toastEl.classList.toggle('text-bg-danger', error);
        new bootstrap.Toast(toastEl).show();
    }

    let claseActual = null; 
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        locale: 'es',
        slotLabelFormat: { hour: '2-digit', minute: '2-digit', hour12: false },
        dayHeaderFormat: { weekday: 'long', day: 'numeric', month: 'numeric' },
        slotMinTime: '06:00:00',
        slotMaxTime: '23:59:00',
        headerToolbar: { left: 'prev,next today', center: 'title', right: 'timeGridWeek,timeGridDay' },
        events: '../../views/clases/clases_profesor.php?json=1',
        eventDidMount: function (info) {
            const lugar = info.event.extendedProps.lugar;
            if (lugar) {
                const titleEl = info.el.querySelector('.fc-event-title');
                if (titleEl) titleEl.innerHTML += `<br><small>${lugar}</small>`;
            }
        },
        eventClick: function (info) {
            const ev = info.event;
            const ext = ev.extendedProps || {};
            claseActual = {
                id_clase: ext.id_clase || ev.id,
                fecha_real: ext.fecha_real || ev.startStr.substring(0, 10),
                event: ev
            };

            const fechaStr = new Date(ev.start).toLocaleString('es-AR', {
                weekday: 'long', day: 'numeric', month: 'numeric', year: 'numeric',
                hour: '2-digit', minute: '2-digit'
            });
            infoEl.innerHTML = `
        <div class="mb-2">
          <strong>${ev.title}</strong><br>
          <small>${fechaStr}</small><br>
          ${ext.lugar ? `<small>Lugar: ${ext.lugar}</small><br>` : ''}
          ${ext.duracion ? `<small>Duración: ${ext.duracion} min</small>` : ''}
        </div>
      `;
            motivoEl.value = '';

            //cargar los inscriptos a una clase particular en las tabs de asistencia e inscriptos
            fetch(`../../views/clases/clases_profesor.php?json=1&id_clase=${claseActual.id_clase}&fecha=${claseActual.fecha_real}`)
                .then(r => r.json())
                .then(inscriptos => {
                    listaInscriptosEl.innerHTML = '';
                    if (!inscriptos.length) {
                        listaInscriptosEl.innerHTML = '<li class="list-group-item text-muted">No hay inscriptos</li>';
                    } else {
                        inscriptos.forEach(a => {
                            const li = document.createElement('li');
                            li.className = 'list-group-item d-flex justify-content-between align-items-center';
                            li.innerHTML = `
                                            <div>
                                                <div class="fw-semibold">${a.nombre}</div>
                                                <small class="text-muted">DNI: ${a.dni}</small>
                                            </div>
                                            `;
                            listaInscriptosEl.appendChild(li);
                        });
                    }

                    fetch('../../views/clases/acciones_clase.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: new URLSearchParams({
                            action: 'ver_asistencia',
                            id_clase: claseActual.id_clase,
                            fecha: claseActual.fecha_real
                        })
                    })
                        .then(r => r.json())
                        .then(data => {
                            contenedorAsistenciaEl.classList.remove('d-none');
                            listaAsistenciaEl.innerHTML = '';

                            if (!inscriptos.length) {
                                listaAsistenciaEl.innerHTML = '<li class="list-group-item text-muted">No hay inscriptos</li>';
                                btnGuardarAsistencia.disabled = true;
                                return;
                            }

                            if (data.estado === 'pasada') {
                                listaAsistenciaEl.innerHTML = `
                                    <li class="list-group-item text-muted">
                                        La clase ya pasó (${claseActual.fecha_real}). No se puede registrar asistencia.
                                    </li>`;
                                btnGuardarAsistencia.disabled = true;
                                return;
                            }

                            if (data.estado === 'futura') {
                                listaAsistenciaEl.innerHTML = `
                                <li class="list-group-item text-muted">
                                    La clase es futura (${claseActual.fecha_real}). La asistencia solo se toma el mismo día.
                                </li>`;
                                btnGuardarAsistencia.disabled = true;
                                return;
                            }

                            if (data.estado === 'pendiente') {
                                const aviso = document.createElement('li');
                                aviso.className = 'list-group-item text-muted';
                                aviso.textContent = 'Tomar asistencia para la clase de hoy';
                                listaAsistenciaEl.appendChild(aviso);
                            }
                            inscriptos.forEach(a => {
                                const estadoPrevio = data.asistencias[a.id_socio];
                                const estadoStr = estadoPrevio !== undefined ? String(estadoPrevio) : null;

                                const li = document.createElement('li');
                                li.className = 'list-group-item d-flex justify-content-between align-items-center';
                                li.innerHTML = `
                                                <div class="me-3">
                                                <div class="fw-semibold">${a.nombre}</div>
                                                <small class="text-muted">DNI: ${a.dni}</small>
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                <label class="form-label mb-0">Estado</label>
                                                <select class="form-select form-select-sm estado-asistencia"
                                                        data-id-socio="${a.id_socio}"
                                                        ${estadoPrevio !== undefined ? 'disabled' : ''}>
                                                    <option value="1" ${estadoStr === "1" ? 'selected' : ''}>Presente</option>
                                                    <option value="0" ${estadoStr === "0" ? 'selected' : ''}>Ausente</option>
                                                </select>
                                                </div>
                                            `;
                                listaAsistenciaEl.appendChild(li);
                            });

                            btnGuardarAsistencia.disabled = (data.estado === 'registrada');
                        });

                });


            modalClase.show();

        }
    });

    calendar.render();

    //event listener cancelar clase
    btnCancelar.addEventListener('click', () => {
        if (!claseActual) return;
        const motivo = motivoEl.value.trim();
        if (!motivo) return mostrarToast('Debes ingresar un motivo', true);

        const body = new URLSearchParams();
        body.append('action', 'cancelar');
        body.append('id_clase', claseActual.id_clase);
        body.append('fecha', claseActual.fecha_real);
        body.append('motivo', motivo);

        fetch('../../views/clases/acciones_clase.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: body.toString()
        })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    mostrarToast('Clase cancelada con éxito');
                    modalClase.hide();
                    calendar.refetchEvents(); 
                } else {
                    mostrarToast(data.error || 'Error al cancelar la clase', true);
                }
            });
    });

    //event listener guardar asistencia
    btnGuardarAsistencia.addEventListener('click', () => {
        if (!claseActual) return;
        const estados = [...document.querySelectorAll('.estado-asistencia')].map(sel => ({
            id_socio: sel.dataset.idSocio,
            asistio: sel.value
        }));

        const body = new URLSearchParams();
        body.append('action', 'asistencia');
        body.append('id_clase', claseActual.id_clase);
        body.append('fecha', claseActual.fecha_real);
        body.append('asistencias', JSON.stringify(estados));

        fetch('../../views/clases/acciones_clase.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: body.toString()
        })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    mostrarToast('Asistencia guardada');
                } else {
                    mostrarToast('Error al guardar asistencia', true);
                }
            });
    });

    //event listener buscar socio para inscribir
    btnBuscarSocio.addEventListener('click', () => {
        const q = buscarInput.value.trim();
        resultadosSociosEl.innerHTML = '';
        if (!q) return;
        fetch(`../../views/socios/buscar_socio.php?q=${encodeURIComponent(q)}`)
            .then(r => r.json())
            .then(socios => {
                if (!socios.length) {
                    resultadosSociosEl.innerHTML = '<li class="list-group-item text-muted">Sin resultados</li>';
                    return;
                }
                socios.forEach(s => {
                    const li = document.createElement('li');
                    li.className = 'list-group-item d-flex justify-content-between align-items-center';
                    li.innerHTML = `
                                <div>
                                <div class="fw-semibold">${s.nombre}</div>
                                <small class="text-muted">DNI: ${s.dni}</small>
                                </div>
                                <button class="btn btn-sm btn-success inscribir-btn" data-id="${s.id_socio}">Inscribir</button>
                            `;
                    resultadosSociosEl.appendChild(li);
                });
            });
    });

    //event listener inscribir socio
    resultadosSociosEl.addEventListener('click', (e) => {
        if (!e.target.classList.contains('inscribir-btn')) return;
        if (!claseActual) return;
        const idSocio = e.target.dataset.id;

        const body = new URLSearchParams();
        body.append('action', 'inscribir');
        body.append('id_clase', claseActual.id_clase);
        body.append('id_socio', idSocio);

        fetch('../../views/clases/acciones_clase.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: body.toString()
        })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    e.target.textContent = 'Inscripto';
                    e.target.disabled = true;
                    mostrarToast('Alumno inscripto');
                } else {
                    if (data.error && data.error.includes('ya esta inscripto')) {
                        e.target.textContent = 'Ya inscripto';
                        e.target.disabled = true;
                    }
                    mostrarToast(data.error || 'Error al inscribir', true);
                }
            });
    });

    //event listener enviar correo 
    btnEnviarCorreo.addEventListener('click', () => {
        if (!claseActual) return;
        const asunto = asuntoEl.value.trim();
        const mensaje = mensajeEl.value.trim();
        if (!asunto || !mensaje) return mostrarToast('Completá asunto y mensaje', true);

        const body = new URLSearchParams();
        body.append('action', 'correo_inscriptos');
        body.append('id_clase', claseActual.id_clase);
        body.append('fecha', claseActual.fecha_real);
        body.append('asunto', asunto);
        body.append('mensaje', mensaje);

        fetch('../../views/clases/acciones_clase.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: body.toString()
        })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    mostrarToast('Correo enviado');
                } else {
                    mostrarToast(data.error || 'Error al enviar correo', true);
                }
            });
    });
});
