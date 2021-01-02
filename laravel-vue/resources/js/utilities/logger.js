const isDev = process.env.NODE_ENV === 'development'

exports.log = (message, ...args) => {
    if (isDev)
        console.log(message, args)
}

exports.error = (err, ...args) => {
    if (isDev)
        console.error(err, args)
}
