import React, {useState} from 'react';
import sanitizeHtml from "sanitize-html"
import ContentEditable from 'react-contenteditable';

function TableColumn( props ) {
    const [ columnData, setColumnData ] = useState( props.data );
    const [ previousData, setPreviousData ] = useState( props.data );

    const onBlur = ( event ) => {
        console.log( columnData );
    }

    const onChange = ( event ) => {
        setColumnData( event.target.value );

        console.log(event.target.value)
    }

    if( props.column === 'id' || props.column === 'ID' ){
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