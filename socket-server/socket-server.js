const express = require('express');
const http = require('http');
const socketIo = require('socket.io');
const cors = require('cors');

const app = express();
app.use(cors());

const server = http.createServer(app);
const io = socketIo(server, {
    cors: {
        origin: "*"
    }
});

io.on('connection', (socket) => {
    console.log('✅ Usuario conectado:', socket.id);

    // Unirse a una sala específica (por ID de tarea)
    socket.on('unirse_a_tarea', (tareaId) => {
        socket.join(tareaId);
        console.log(`📌 Usuario ${socket.id} se unió a la tarea ${tareaId}`);
    });

    // Notificar a otros usuarios en la misma sala cuando se actualiza una tarea
    socket.on('tarea_actualizada', (data) => {
        const { tareaId, ...resto } = data;

        if (tareaId) {
            socket.to(tareaId).emit('notificacion_tarea', resto);
            console.log(`🔔 Notificación enviada a sala ${tareaId}:`, resto);
        } else {
            console.warn('⚠️ tareaId no proporcionado en tarea_actualizada');
        }
    });

    socket.on('disconnect', () => {
        console.log('❌ Usuario desconectado:', socket.id);
    });
});

server.listen(3000, () => {
    console.log('🚀 Servidor Socket.IO corriendo en http://localhost:3000');
});
