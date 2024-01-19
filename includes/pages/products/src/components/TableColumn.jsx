import React, {useState} from 'react';
import sanitizeHtml from "sanitize-html"
import ContentEditable from 'react-contenteditable';

function TableColumn( props ) {
    const [ columnData, setColumnData ] = useState( props.data );
    const [ previousData, setPreviousData ] = useState( props.data );

    const onChange = ( event ) => {
        setColumnData( event.target.value );
    }

    let editable = props.editable;

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
                <ContentEditable html={ columnData }  onChange={ onChange } onBlur={ props.onBlur }/>
            </td>
        );
    }
}

export default TableColumn;