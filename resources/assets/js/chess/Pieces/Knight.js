import Piece from './Piece'

export default class Knight extends Piece {
  constructor(color) {
    super()

    this.color = color
    this.name = 'knight'

    this.buildClass()
  }
}
