const httpServer = require('http').createServer()
const io = require('socket.io')(httpServer)

httpServer.listen(8080, function () {
	console.log('listening on *:8080')
})

io.on('connection', function (socket) {
	console.log('Client has connected. Socket ID = ' + socket.id)
})
