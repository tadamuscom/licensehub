import { __ } from '@wordpress/i18n';
import { TableColumn } from '@global';

export const TableRow = ({
	columns,
	editable,
	deletable = true,
	onBlur,
	onDelete,
	headers,
	...props
}) => {
	const renderColumns = () => {
		let returnable = [];

		for (const column of columns) {
			returnable.push(
				<TableColumn
					data={column}
					key={Math.random()}
					column={headers[columns.indexOf(column)]}
					editable={editable}
					onBlur={onBlur}
					row={columns}
					{...props}
				/>,
			);
		}

		return returnable;
	};

	return (
		<tr>
			{renderColumns()}
			{deletable && (
				<td>
					<button
						className="cursor-pointer text-md tada-delete-btn"
						onClick={onDelete}>
						{__('Delete', 'licensehub')}
					</button>
				</td>
			)}
		</tr>
	);
};
