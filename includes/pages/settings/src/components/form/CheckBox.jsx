import React, {useState} from 'react';

function CheckBox( props ) {
    const [value, setValue] = useState( props.value );

    const onChange = ( e ) => {
        setValue( !value )
    }

    return (
        <div>
            <label className="checkbox-container tada-flex-row">
                <span className='checkbox-label'>{ props.label }</span>
                <input type="checkbox" checked={ value } name={ props.name } id={ props.id } onClick={ props.wrapperOnClick } onChange={ onChange } />
                    <span className="checkmark"></span>
            </label>
        </div>
    );
}

export default CheckBox;