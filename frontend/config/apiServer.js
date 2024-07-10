export const apiServer = {
  host: 'http://test.server',
  port: 80,

  getDomain: function() {
    return this.host + ':' + this.port;
  },
}
