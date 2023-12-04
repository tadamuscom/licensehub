import React from 'react';
import TableColumn from "./TableColumn";

function TableRow(props ) {
    return (
        <tr>
            <TableColumn data={ props.columns.id } />
            <TableColumn data={ props.columns.name } />
            <TableColumn data={ props.columns.status } />
            <TableColumn data={ props.columns.created_at } />
        </tr>
    );
}

export default TableRow;