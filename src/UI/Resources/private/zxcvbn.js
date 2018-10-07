const BasicCheck = function (text) {
    let score = 0;
    if (text.length > 4) score++;
    if ((text.match(/[a-z]/)) && (text.match(/[A-Z]/))) score++;
    if (text.match(/\d+/)) score++;
    if (text.match(/\W/)) score++;
    if (text.length > 12) score++;

    return {
        score: score,
        feedback: {
            suggestions: [],
            warning: null
        }
    };
};

module.exports = window.ChangPws = function (text, algorithm) {
    if ('basic' === algorithm) {
        return BasicCheck(text);
    }

    return require('zxcvbn')(text);
};
