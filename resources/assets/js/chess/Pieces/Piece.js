export default class Piece {
  buildClass() {
    this.class = `${this.color}-${this.name}`
  }

  getClass() {
    return this.class
  }

  isOfColor(color) {
    return color === this.color
  }
}
