import React, {useState} from 'react';
import sanitizeHtml from "sanitize-html"
import ContentEditable from 'react-contenteditable';

function TableColumn( props ) {
    const [ columnData, setColumnData ] = useState( props.data );
    const [ previousData, setPreviousData ] = useState( props.data );

    const onBlur = ( event ) => {
        console.log( event.currentTarget.innerHTML );
    }

    const onChange = ( event ) => {
        setColumnData( event.target.value );
    }

    let editable = true;

    if( props.column === 'id' ){
        editable = false;
    }

    if( props.column === 'ID' ){
        editable = false;
    }

    if( props.column === 'created_at' ){
        editable = false;
    }

    switch ( props.column ){
        case 'ID':
        case 'id':
        case 'created_at':
        case 'expires_at':
            editable = false;
    }

    if( ! editable ){
        return (
            <td column={ props.column }>
                { columnData }
            </td>
        );
    }else{
        return (
            <td column={ props.column }>
                <ContentEditable html={ columnData }  onChange={ onChange } onBlur={ onBlur }/>
            </td>
        );
    }
}

export default TableColumn;