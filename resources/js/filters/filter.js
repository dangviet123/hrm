import Vue from 'vue';
import Notifications from 'vue-notification'
Vue.use(Notifications)


Vue.filter('fomatDateTime', value => {
  if (isNaN(value)) {
    return moment(value).format("DD-MM-YYYY HH:mm:ss");
  }
});

Vue.filter('fomatDate', value => {
  if (isNaN(value)) {
    return moment(value).format("DD-MM-YYYY");
  }
});

Vue.filter('fomatDateMonthYear', value => {
  if (isNaN(value)) {
    return moment(value).format("MM-YYYY");
  }
});

Vue.filter('prettyBytes', function (num) {
    if (typeof num !== 'number' || isNaN(num)) {
      throw new TypeError('Expected a number');
    }
  
    var exponent;
    var unit;
    var neg = num < 0;
    var units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
  
    if (neg) {
      num = -num;
    }
  
    if (num < 1) {
      return (neg ? '-' : '') + num + ' B';
    }
  
    exponent = Math.min(Math.floor(Math.log(num) / Math.log(1000)), units.length - 1);
    num = (num / Math.pow(1000, exponent)).toFixed(2) * 1;
    unit = units[exponent];
  
    return (neg ? '-' : '') + num + ' ' + unit;
});

Vue.filter('ReadNumbersIntoWords', value => {
    const num2Word2 =  function() {
      var t = ["không", "một", "hai", "ba", "bốn", "năm", "sáu", "bảy", "tám", "chín"],
          r =  function(r, n) {
              var o = "",
                  a = Math.floor(r / 10),
                  e = r % 10;
              return a > 1 ? (o = " " + t[a] + " mươi", 1 == e && (o += " mốt")) : 1 == a ? (o = " mười", 1 == e && (o += " một")) : n && e > 0 && (o = " lẻ"), 5 == e && a >= 1 ? o += " lăm" : 4 == e && a >= 1 ? o += " tư" : (e > 1 || 1 == e && 0 == a) && (o += " " + t[e]), o
          },
          n =  function(n, o) {
            var a = "",
                e = Math.floor(n / 100),
                n = n % 100;
            return o || e > 0 ? (a = " " + t[e] + " trăm", a += r(n, !0)) : a = r(n, !1), a
          },
          o = function(t, r) {
            var o = "",
                a = Math.floor(t / 1e6),
                t = t % 1e6;
            a > 0 && (o = n(a, r) + " triệu", r = !0);
            var e = Math.floor(t / 1e3),
                t = t % 1e3;
            return e > 0 && (o += n(e, r) + " ngàn", r = !0), t > 0 && (o += n(t, r)), o
          };
      return {
        convert: function(r) {
          if (0 == r) return t[0];
          var n = "",a = "";
            do{
              var ty = r % 1e9, r = Math.floor(r / 1e9), n = r > 0 ? o(ty, !0) + a + n : o(ty, !1) + a + n, a = " tỷ"; 
            }
          while (r > 0);
          return n.trim();
        }
      }
  }();
  return num2Word2.convert(value);
});




