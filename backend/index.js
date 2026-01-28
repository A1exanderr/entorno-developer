const express = require('express');
const http = require('http');
const WebSocket = require('ws');
const cors = require('cors');

const app = express();
app.use(cors());

const server = http.createServer(app);
const wss = new WebSocket.Server({ server });

// 🔹 Estado global
let carrito = [];

// WebSocket
wss.on('connection', (ws) => {
  console.log('Cliente conectado');
  console.log('Conectados:', wss.clients.size);

  ws.on('close', () => {
    console.log('Cliente desconectado');
    console.log('Conectados:', wss.clients.size);
  });

  // Enviar estado inicial (CLON)
  ws.send(JSON.stringify({
    type: 'carrito',
    carrito: [...carrito]
  }));

  ws.on('message', (message) => {
    const data = JSON.parse(message);

    if (data.type === 'agregar') {
      carrito.push(data.item);
    }

    if (data.type === 'eliminar') {
      carrito = carrito.filter(p => p.id !== data.item.id);
    }

    // 🔥 BROADCAST CON NUEVA REFERENCIA
    wss.clients.forEach(client => {
      if (client.readyState === WebSocket.OPEN) {
        client.send(JSON.stringify({
          type: 'carrito',
          carrito: [...carrito]
        }));
      }
    });
  });
});

server.listen(3000, () => {
  console.log('Servidor WebSocket en http://localhost:3000');
});
