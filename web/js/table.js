var DOM = React.DOM;
/*class TableComponent extends React.Component {
  render () {
    var rows = this.props.data.map(function (row) {
      var cells = row.map(function (cell) {
        return `<td>{cell}</td>`;
      });

      return `<tr>{cells}</tr>`;
    });
    return (
      `<table>
        <tbody>{rows}</tbody>
      </table>`
    );
  }
}*/

class TableComponent extends React.Component {
  render () {
   var rows = this.props.data.map(function (row, index) {
      var cells = row.map(function (cell, id) {
        return DOM.td({key : id}, cell);
      });

      return DOM.tr({key: index}, cells);
    });

    return (
      DOM.table(null, DOM.tbody(null, rows)));
  }
}

class TestComponent extends React.Component {
  render() { return (`<div>Hello World</div>`); }
}