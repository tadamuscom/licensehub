import React from 'react';

function TableHeader(props) {
    return (
        <th>
            { props.content }
        </th>
    );
}

export default TableHeader;