const express = require('express');
const cors = require('cors');
const { WebSocketServer } = require('ws');

const app = express();
app.use(cors());
app.use(express.json());

const PORT = 3000;

// Datos de ejemplo: carrito
let carrito = [];

// Servidor HTTP
const server = app.listen(PORT, () => {
  console.log(`Servidor escuchando en http://localhost:${PORT}`);
});

// WebSocket
const wss = new WebSocketServer({ server });

wss.on('connection', (ws) => {
  console.log('Cliente conectado a WebSocket');
  // Enviar carrito actual al cliente
  ws.send(JSON.stringify({ carrito }));

  ws.on('message', (msg) => {
    const data = JSON.parse(msg);
    if (data.action === 'agregar') {
      carrito.push(data.item);
    }
    if (data.action === 'eliminar') {
      carrito = carrito.filter(i => i.id !== data.item.id);
    }

    // Broadcast a todos los clientes
    wss.clients.forEach(client => {
      if (client.readyState === 1) {
        client.send(JSON.stringify({ carrito }));
      }
    });
  });
});

// Ruta HTTP para prueba
app.get('/', (req, res) => {
  res.send('Servidor Node.js + WebSocket para carrito 🚀');
});
