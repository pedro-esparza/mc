function choosePromotion() {
    bootbox.prompt({
        title: "Please choose a promotion",
        message: '<p>Please select an option below:</p>',
        inputType: 'radio',
        inputOptions: [
        {
            text: 'Queen',
            value: 'q',
        },
        {
            text: 'Rook',
            value: 'r',
        },
        {
            text: 'Knight',
            value: 'n',
        },
        {
            text: 'Bishop',
            value: 'b',
        }
        ],
        callback: function (val) {
            return val;
            console.log(val);
        }
    });
}