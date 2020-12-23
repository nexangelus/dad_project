const express = require("express");
const app = express();
const httpServer = require('http').Server(app);
const io = require('socket.io')(httpServer);
const port = 8080;

const SessionManager = require("./SessionManager.js")
const sessions = new SessionManager();

app.use(express.json());

app.post('/login', (req, res) => {
    if(req.body.user && req.body.socketID) {
        const user = req.body.user;
        sessions.addUserSession(user, req.body.socketID);

        io.sockets.connected[req.body.socketID].join(user.type);

        console.log('User Logged: UserID= ' + user.id + ' Socket ID= ' + req.body.socketID)
        console.log('  -> Total Sessions= ' + sessions.users.size)
        return res.json({"status": "OK"})
    }
    return res.json({"status": "ERROR"})
})

app.post('/logout', (req, res) => {
    if(req.body.user) {
        const user = req.body.user;
        const session = sessions.getUserSession(user.id);
        console.log(sessions.users)
        io.sockets.connected[session.socketID].leave(user.type);

        sessions.removeUserSession(user.id);

        console.log('User Logged OUT: UserID= ' + user.id + ' Socket ID= ' + session.socketID)
        console.log('  -> Total Sessions= ' + sessions.users.size)
        return res.json({"status": "OK"})
    }
    return res.json({"status": "ERROR"})
})

app.post('/idChanged', (req, res) => {
    if(req.body.user && req.body.socketID) {
        const user = req.body.user;
        sessions.addUserSession(user, req.body.socketID);

        io.sockets.connected[req.body.socketID].join(user.type);

        console.log('User ReLogged: UserID= ' + user.id + ' Socket ID= ' + req.body.socketID)
        console.log('  -> Total Sessions= ' + sessions.users.size)
        return res.json({"status": "OK"})
    }
    return res.json({"status": "ERROR"})
})

app.get('/m/:id/:m', (req, res) => {
    const session = sessions.getUserSession(parseInt(req.params.id))
    console.log(session);
    io.to(session.socketID).emit("pm", req.params.m);
    res.send("ok");
})



io.on('connection', function (socket) {
    console.log('Client has connected. Socket ID = ' + socket.id)
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