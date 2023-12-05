import React, {useState} from 'react';

function SingleTextInput( props ) {
    const [value, setValue] = useState( props.value );

    const onChange = ( e ) => {
        setValue(e.target.value)
    }

    return (
        <div>
            <input type="text" autoComplete="off" id={ props.id } name={ props.name } value={ value } onChange={onChange} />
        </div>
    );
}

export default SingleTextInput;