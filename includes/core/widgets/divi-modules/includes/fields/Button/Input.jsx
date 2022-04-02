// External Dependencies
import React, { Component } from 'react';

// Internal Dependencies
import './style.css';

class Button extends Component {

  static slug = 'wpfnl_input';

  /**
   * Handle input value change.
   *
   * @param {object} event
   */
  _onChange = (event) => {
    this.props._onChange(this.props.name, event.target.value);
  }

  render() {
    return(
      <button>{ this.props.name}</button>
    );
  }
}

export default Button;
