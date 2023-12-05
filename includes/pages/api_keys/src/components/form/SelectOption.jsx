import React from 'react';

function SelectOption(props) {
    return (
        <option value={ props.id }>
            { props.label }
        </option>
    );
}

export default SelectOption;