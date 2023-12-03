import React, {useState} from 'react';

function SinglePasswordInput(props) {
    const [value, setValue] = useState( props.value );

    const onChange = ( e ) => {
        setValue(e.target.value)
    }

    return (
        <div>
            <input type="password" autoComplete="off" id={ props.id } name={ props.name } value={ value } onChange={onChange} />
        </div>
    );
}

export default SinglePasswordInput;