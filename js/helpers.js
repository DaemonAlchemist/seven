/**
 * Created by Andy on 7/22/2016.
 */

function _H(template, context) {
    return Handlebars.compile(template)(context);
}

function _T(template, context) {
    return _H($("script#" + template).html(), context);
}
