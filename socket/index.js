const express = require("express");
const axios = require("axios");
const app = express();
const httpServer = require('http').Server(app);
const io = require('socket.io')(httpServer);
const port = 8080;
const config = require('./config');
const PASSWORD_LARAVELAPI_SOCKET = config.API_PW;

const SessionManager = require("./SessionManager.js")
const sessions = new SessionManager();

app.use(express.json());

// Para cada pedido a esta API, verificar se existe um campo com a password para comunicação entre API Laravel - Socket
app.use((req, res, next) => {
    if(req.body.pw && req.body.pw === PASSWORD_LARAVELAPI_SOCKET){
        next();
    } else {
        console.error(`[REQUEST] PASSWORD NOT PRESENT OR WRONG. URL ${req.url}, PASSWORD: ${req.body.pw}`);
        res.end("PW is not correct.");
    }
});

app.post('/login', (req, res) => {
    res.json({"status": "OK"})
    if(req.body.user && req.body.socketID) {
        const user = req.body.user;

        console.log(`[/login] ID: '${req.body.user.id}', Type: '${user.type}', SocketID: ${req.body.socketID}`);
        sessions.addUserSession(user, req.body.socketID);

        io.sockets.connected[req.body.socketID].join(user.type);
    }
})

app.post('/logout', (req, res) => {
    res.json({"status": "OK"})
    if(req.body.user) {
        console.log(`[/logout] ID: '${req.body.user.id}', SocketID: ${req.body.socketID}`);

        const user = req.body.user;
        const session = sessions.getUserSession(user.id);
        if(session) {
            io.sockets.connected[session.socketID].leave(user.type);
            sessions.removeUserSession(user.id);
        } else {
            console.log(`[/logout] UserID '${user.id}' logged out without session to socket.`);
        }
    }
})

app.post('/idChanged', (req, res) => {
    res.json({"status": "OK"})
    if(req.body.user && req.body.socketID) {
        console.log(`[/idChanged] ID: '${req.body.user.id}', Type: '${req.body.user.type}', SocketID: ${req.body.socketID}`);

        const user = req.body.user;
        sessions.addUserSession(user, req.body.socketID);

        io.sockets.connected[req.body.socketID].join(user.type);

    }
})

app.post('/newOrderForCustomer', (req, res) => {
    res.json({"status": "OK"})
    if(req.body.cookID) {
        console.log(`[/newOrderForCustomer] CookID: '${req.body.cookID}'`);
        const session = sessions.getUserSession(parseInt(req.body.cookID))
        if(session) {
            io.to(session.socketID).emit("newOrder");
            console.log(`[/newOrderForCustomer] io.to(${session.socketID}).emit('newOrder')`);
        } else {
            console.error(`[/newOrderForCustomer] No session for Cook with ID: ${req.body.cookID}`);
        }
    }
})

app.post('/updateOrdersTableManager', (req, res) => {
    res.json({"status": "OK"})
    if(req.body.order) {
        console.log(`[/updateOrdersTableManager] OrderID: '${req.body.order.id}'`);
        io.to('EM').emit("updateOrdersTable", req.body.order);
        console.log(`[/updateOrdersTableManager] io.to('EM').emit("updateOrdersTable", ${JSON.stringify(req.body.order)});`);
    }
})

app.post('/updatedEmployeeForManagers', (req, res) => {
    res.json({"status": "OK"})
    if(req.body.user) {
        console.log(`[/updatedEmployeeForManagers] UserID: '${req.body.user.id}'`);
        io.to('EM').emit("updatedEmployee", req.body.user);
        console.log(`[/updatedEmployeeForManagers] io.to('EM').emit("updatedEmployee", ${JSON.stringify(req.body.user)});`);
    }
})

app.post('/updateCustomerOrder', (req, res) => {
    res.json({"status": "OK"})
    if(req.body.order) {
        console.log(`[/updateCustomerOrder] OrderID: '${req.body.order.id}'`);
        const session = sessions.getUserSession(parseInt(req.body.order.customer_id))
        if (session){
            io.to(session.socketID).emit("updateOrder", req.body.order);
            console.log(`[/updateCustomerOrder] io.to(${session.socketID}).emit("updateOrder", ${JSON.stringify(req.body.order)});`);
        } else {
            console.error(`[/updateCustomerOrder] No session for Customer with ID: ${req.body.order.customer_id}`);
        }
    }
})

app.post('/userBlocked', (req, res) => {
    res.json({"status": "OK"})
    if(req.body.userID) {
        console.log(`[/userBlocked] User ID: '${req.body.userID}'`);
        const session = sessions.getUserSession(parseInt(req.body.userID))
        if (session){
            io.to(session.socketID).emit("userBlocked");
            console.log(`[/userBlocked] io.to(${session.socketID}).emit("userBlocked");`);
        } else {
            console.error(`[/userBlocked] No session for User ID: ${req.body.userID}`);
        }
    }
})

app.post('/orderInTransitForDeliverers', (req, res) => {
    res.json({"status": "OK"})
    if(req.body.orderID) {
        console.log(`[/orderInTransitForDeliverers] Order ID: '${req.body.orderID}'`);
        io.to('ED').emit("alreadyInTransit", req.body.orderID);
        console.log(`[/orderInTransitForDeliverers] io.to('ED').emit("alreadyInTransit", ${req.body.orderID});`)
    }
})

app.post('/newOrderForDelivery', (req, res) => {
    res.json({"status": "OK"})
    if(req.body.order) {
        console.log(`[/newOrderForDelivery] Order ID: '${req.body.order}'`);
        io.to('ED').emit("newOrder", req.body.order);
        console.log(`[/newOrderForDelivery] io.to('ED').emit("newOrder", ${JSON.stringify(req.body.order)});`)
    }
})

app.post('/orderCancelled', (req, res) => {
    res.json({"status": "OK"})
    if(req.body.userID && req.body.orderID) {
        console.log(`[/orderCancelled] Order ID: '${req.body.order}', User ID: ${req.body.userID}`);
        const session = sessions.getUserSession(parseInt(req.body.userID))
        if (session){
            io.to(session.socketID).emit("orderCancelled", req.body.orderID);
            console.log(`[/orderCancelled] io.to(${session.socketID}).emit("orderCancelled", ${req.body.orderID});`);
        } else {
            console.error(`[/orderCancelled] No session for Customer with ID: ${req.body.userID}`);
        }
    }
})

io.on('connection', function (socket) {
    console.log(`[new connection] SocketID: ${socket.id}`);

    /** O código de emitir os eventos para o Socket é tratado no lado do servidor
     *  Principalmente na classe App\Listeners\OrderSaveListener e App\Listeners\UserSaveListener
     *  Que quando algum desses modelos são gravados, é que é enviada a mensagem para as pessoas que são precisas.
     *  Confirmar também a classe App\Utils\SocketIO que envia todos os pedidos para a API Rest Express do Socket.
     */

    socket.on('disconnect', (reason) => {
        if(reason === "transport close") {
            let clientID = sessions.getUserBySessionID(socket.id);
            if(clientID) {
                sessions.removeSocketIDSession(socket.id);
                console.log("[SOCKET DISCONNECTED] Waiting 5 minutes to check for reconnect.");
                setTimeout(() => {
                    let session = sessions.getUserSession(clientID);
                    if(session) {
                        console.log("[SOCKET DISCONNECT] User has reconnected, cancelling logout");
                    } else {
                        console.log("[SOCKET DISCONNECT] User has not reconnected. Notifying database of logout.");
                        axios.post(`${config.API_URL}api/socket-logout`, {pw: config.API_PW, id: clientID})
                    }
                }, 1000 * 60 * 5); // miliseconds * 60 (seconds) * 5 (minutes)
            }
        }
    })
})


httpServer.listen(port, function () {
    console.log(`listening on *:${port}`)
})