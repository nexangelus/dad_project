exports.show = (title, message, callback, ctx) => {
    ctx.$bvModal.msgBoxConfirm(message, {
        title: title,
        size: 'sm',
        buttonSize: 'sm',
        okVariant: 'danger',
        okTitle: 'Yes',
        cancelTitle: 'No',
        footerClass: 'p-2',
        hideHeaderClose: false,
        centered: true,
    }).then(value => {
        callback(value);
    }).catch(err => {
        callback(null);
    })
}
