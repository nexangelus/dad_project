const express = require("express");
const app = express();
const httpServer = require('http').Server(app);
const io = require('socket.io')(httpServer);
const port = 8080;

const SessionManager = require("./SessionManager.js")
const sessions = new SessionManager();

app.use(express.json());

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

app.get('/m/:id/:m', (req, res) => {
    const session = sessions.getUserSession(parseInt(req.params.id))
    console.log(session);
    io.to(session.socketID).emit("pm", req.params.m);
    res.send("ok");
})



io.on('connection', function (socket) {
    console.log(`[new connection] SocketID: ${socket.id}`);
    /*socket.on('user_logged', (user) => {
        if (user) {
            sessions.addUserSession(user, socket.id)
            socket.join(user.type)
            console.log('User Logged: UserID= ' + user.id + ' Socket ID= ' + socket.id)
            console.log('  -> Total Sessions= ' + sessions.users.size)
        }
    })
    socket.on('user_logged_out', (user) => {
        if (user) {
            socket.leave(user.type)
            sessions.removeUserSession(user.id)
            console.log('User Logged OUT: UserID= ' + user.id + ' Socket ID= ' + socket.id)
            console.log('  -> Total Sessions= ' + sessions.users.size)
        }
    })*/
})


httpServer.listen(port, function () {
    console.log(`listening on *:${port}`)
})