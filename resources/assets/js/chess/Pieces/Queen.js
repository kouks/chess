import Piece from './Piece'

export default class Queen extends Piece {
  constructor(color) {
    super()

    this.color = color
    this.name = 'queen'

    this.buildClass()
  }
}
