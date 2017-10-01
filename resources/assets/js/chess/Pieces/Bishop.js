import Piece from './Piece'

export default class Bishop extends Piece {
  constructor(color) {
    super()

    this.color = color
    this.name = 'bishop'

    this.buildClass()
  }
}
