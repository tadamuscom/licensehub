import React from 'react';

function TableColumn( props ) {
    return (
        <td column={ props.column }>
            { props.data }
        </td>
    );
}

export default TableColumn;