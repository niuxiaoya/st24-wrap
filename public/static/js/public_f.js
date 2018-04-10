function getUrlData(name) {
    let reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
    let r = window.location.search.replace(`?`, '').match(reg);
    if (r != null) return unescape(r[2]);
    return null;
}