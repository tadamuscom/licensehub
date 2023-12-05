import React, {useState} from 'react';
import TableColumn from "./TableColumn";

function TableRow(props ) {
    const preColumns = [];

    for ( const [ key, value ] of Object.entries( props.columns ) ) {
        preColumns.push( <TableColumn data={ value } key={ key } /> );
    }

    const [ columns, setColumns ] = useState( preColumns );

    return (
        <tr>
            { columns }
        </tr>
    );
}

export default TableRow;