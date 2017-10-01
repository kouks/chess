import King from './Pieces/King'
import Queen from './Pieces/Queen'
import Rook from './Pieces/Rook'
import Knight from './Pieces/Knight'
import Bishop from './Pieces/Bishop'
import Pawn from './Pieces/Pawn'

export default class Board {
  constructor() {
    this.moves = [];
  }

  getPieces(move) {
    return this.calculateFromMoves(move)
  }

  setMoves(moves) {
    this.moves = moves
  }

  getMoves() {
    return this.moves
  }

  /**
   * We calculate the current position from provided moves.
   */
  calculateFromMoves(rollback) {
    let position = this.defaultPosition()

    this.moves.every((item) => {
      let piece = position[item.from]

      delete position[item.from]

      position[item.to] = piece

      if (rollback !== false && rollback === item) {
        return false
      }

      return true
    })

    return position
  }

  /**
   * We set the default chess position.
   */
  defaultPosition() {
    return {
      '00': new Rook('black'), '10': new Knight('black'), '20': new Bishop('black'), '30': new Queen('black'), '40': new King('black'), '50': new Bishop('black'), '60': new Knight('black'), '70': new Rook('black'), '01': new Pawn('black'), '11': new Pawn('black'), '21': new Pawn('black'), '31': new Pawn('black'), '41': new Pawn('black'), '51': new Pawn('black'), '61': new Pawn('black'), '71': new Pawn('black'), '07': new Rook('white'), '17': new Knight('white'), '27': new Bishop('white'), '37': new Queen('white'), '47': new King('white'), '57': new Bishop('white'), '67': new Knight('white'), '77': new Rook('white'), '06': new Pawn('white'), '16': new Pawn('white'), '26': new Pawn('white'), '36': new Pawn('white'), '46': new Pawn('white'), '56': new Pawn('white'), '66': new Pawn('white'), '76': new Pawn('white')
    }
  }
}
