import React, {useState} from 'react';
import sanitizeHtml from "sanitize-html"
import ContentEditable from 'react-contenteditable';

function TableColumn( props ) {
    const [ columnData, setColumnData ] = useState( props.data );
    const [ previousData, setPreviousData ] = useState( props.data );

    const getElementID = ( row ) => {
        let returnable;

        row.childNodes.forEach( ( element, index ) => {
            switch( element.getAttribute( 'column' ) ){
                case 'id':
                case 'ID':
                    returnable = element.innerText;
            }
        } );

        return returnable;
    }

    const onBlur = ( event ) => {
        const table = event.currentTarget.parentNode.parentNode.parentNode.parentNode;
        const row = event.currentTarget.parentNode.parentNode;
        const column = event.currentTarget.parentNode.getAttribute( 'column' );
        const value = event.currentTarget.innerHTML;
        const id = getElementID( row );

        console.log(table)

        if ( column === 'status' ){
            if ( value !== 'active' || value !== 'inactive' ){
                column.style.border = '1px solid red';

                return;
            }
        }
    }

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
                <ContentEditable html={ columnData }  onChange={ onChange } onBlur={ onBlur }/>
            </td>
        );
    }
}

export default TableColumn;