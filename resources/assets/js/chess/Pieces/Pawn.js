import Piece from './Piece'

export default class Pawn extends Piece {
  constructor(color) {
    super()

    this.color = color
    this.name = 'pawn'

    this.buildClass()
  }
}
