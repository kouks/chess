export default {
  default: {
    '00': 'black-rook', '10': 'black-knight', '20': 'black-bishop', '30': 'black-queen', '40': 'black-king', '50': 'black-bishop', '60': 'black-knight', '70': 'black-rook', '01': 'black-pawn', '11': 'black-pawn', '21': 'black-pawn', '31': 'black-pawn', '41': 'black-pawn', '51': 'black-pawn', '61': 'black-pawn', '71': 'black-pawn', '07': 'white-rook', '17': 'white-knight', '27': 'white-bishop', '37': 'white-queen', '47': 'white-king', '57': 'white-bishop', '67': 'white-knight', '77': 'white-rook', '06': 'white-pawn', '16': 'white-pawn', '26': 'white-pawn', '36': 'white-pawn', '46': 'white-pawn', '56': 'white-pawn', '66': 'white-pawn', '76': 'white-pawn'
  },

  /**
   * We calculate the current position from provided moves.
   */
  calculateFromMoves(moves) {
    // We do not want to pass by reference.
    let position = $.extend({}, this.default)

    moves.forEach((item) => {
      let piece = position[item.from]

      delete position[item.from]

      position[item.to] = piece
    })

    return position
  }
}
