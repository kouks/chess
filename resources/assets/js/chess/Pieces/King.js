import Piece from './Piece'

export default class King extends Piece {
  constructor(color) {
    super()

    this.color = color
    this.name = 'king'

    this.buildClass()
  }
}
