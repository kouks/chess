import Piece from './Piece'

export default class Rook extends Piece {
  constructor(color) {
    super()

    this.color = color
    this.name = 'rook'

    this.buildClass()
  }
}
