import React from 'react';
import TableColumn from "./TableColumn";

function TableRow(props ) {
    const columns = [];

    if( props.columns && props.columns.length > 0 ){
        props.columns.forEach( ( element, index ) => {
            columns.push( <TableColumn data={ element } key={ index } /> );
        } );
    }

    return (
        <tr>
            { columns }
        </tr>
    );
}

export default TableRow;