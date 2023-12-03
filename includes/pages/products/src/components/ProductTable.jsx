import React, { useState } from 'react';
import TableHeader from "./TableHeader";
import TableRow from "./TableRow";

function ProductTable( props ) {
    const products = [];
    const fields = [];

    lchb_products.fields.forEach( ( element, index ) => {
        fields.push( <TableHeader content={ element } key={ index } /> );
    } );

    lchb_products.products.forEach( ( element, index ) => {
        products.push( <TableRow columns={ element } key={ index } /> );
    } );

    return (
        <div>
            <table className='tada-table'>
                <thead>
                    <tr>
                        { fields }
                    </tr>
                </thead>
                <tbody>
                    { products }
                </tbody>
            </table>
        </div>
    );
}

export default ProductTable;