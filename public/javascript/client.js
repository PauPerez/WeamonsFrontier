window.onload = function () {
  let host = "localhost";
  let urlws = `ws://${host}:8080/ws`;
  let api = `http://${host}:8080/api`;
  let socket = new WebSocket(urlws);;

  // connect
   function connect() {
    let outgoingMessage = { tipus: "login", content: `${document.forms.publish.user.value}` };
    socket.send(JSON.stringify(outgoingMessage));
  };

  // handle incoming messages
  socket.onmessage = function (event) {
    let incomingMessage = event.data;
    showMessage(incomingMessage);
  };

  socket.onclose = event => console.log(`Closed ${event.code}`);

  // send message from the form
  document.forms.publish.onsubmit = function () {
    let outgoingMessage = { tipus: "data", content: `${this.message.value}` };
    socket.send(JSON.stringify(outgoingMessage));
    this.message.value="";
    return false;
  };

  // show message in div#messages
  function showMessage(message) {
    let messageElem = document.createElement('div');
    messageElem.textContent = message;
    document.getElementById('messages').prepend(messageElem);
  }

  setTimeout(connect, 200);
};