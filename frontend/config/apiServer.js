export const apiServer = {
  host: 'http://localhost',
  port: 80,

  getDomain: function() {
    return this.host + ':' + this.port;
  },
}
