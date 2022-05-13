/**
Before running:
> npm install ws
Then:
> node server.js
> open http://localhost:8080 in the browser
*/

const http = require('http');
const fs = require('fs');
const ws = new require('ws');
const port = 8080;

const wss = new ws.Server({ noServer: true });
http.createServer(accept).listen(port);
let log = console.log;
log(`WS connected at port: ${port}`);

const clients = new Set();

function accept(req, res) {

  switch (req.url) {
    case '/ws':
      if (req.headers.upgrade && req.headers.upgrade.toLowerCase() == 'websocket' &&
        // can be Connection: keep-alive, Upgrade
        req.headers.connection.match(/\bupgrade\b/i)) {
        wss.handleUpgrade(req, req.socket, Buffer.alloc(0), onSocketConnect);
      }
      break;
    case '/':  // index.html
      fs.createReadStream('./chat.html.twig').pipe(res);
      break;
    case '/api':
      res.writeHead(200, { 'Content-Type': 'application/json', 'Access-Control-Allow-Origin': '*' })
      let noms = [];
      clients.forEach(function (value) {
        //console.log(JSON.stringify(value.nom))
        noms.push(value.nom);
      })
      const responseBody = { at1: "Hola", at2: "adios", data: noms };
      res.write(JSON.stringify(responseBody));
      res.end();
      break;
    default:    // page not found
      res.writeHead(404);
      res.end();
  }
}

function onSocketConnect(ws) {
  clients.add(ws);
  log(`new connection`);

  ws.on('message', function (message) {
    let obj = JSON.parse(message);
    switch (obj.tipus) {
      case "login":
        ws.nom = obj.content;
        log("Connection from " + ws.nom + " remote Address:" + ws._socket.remoteAddress);
        break;
      case "data":
        log(`message received from ${ws.nom}: ${obj.tipus} - ${obj.content}`);
        //log(`message received: ${message}`);
        for (let client of clients) {
          client.send(ws.nom + ":" + obj.content);
        }
        break;
      default:
        log("Tipus erroni:" + obj.tipus);
    }
  });

  ws.on('close', function () {
    log(`connection closed`);
    clients.delete(ws);
  });
}
