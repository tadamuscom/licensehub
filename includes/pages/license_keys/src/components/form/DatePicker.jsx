import React from 'react';

function DatePicker(props) {
    return (
        <div>
            <input type="date" id={ props.id } name={ props.name } min={ props.min } max={ props.max } />
        </div>
    );
}

export default DatePicker;