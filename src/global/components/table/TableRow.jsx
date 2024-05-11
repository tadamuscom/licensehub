import { __ } from '@wordpress/i18n';
import { TableColumn } from '@global/components/table/TableColumn';

export const TableRow = ({ columns, editable, onBlur, onDelete, ...props }) => {
	const renderColumns = () => {
		let returnable = [];

		for (const column of columns) {
			returnable.push(
				<TableColumn
					data={column}
					key={Math.random()}
					column={column.name}
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
			<td>
				<button
					className="cursor-pointer text-md tada-delete-btn"
					onClick={onDelete}>
					{__('Delete', 'licensehub')}
				</button>
			</td>
		</tr>
	);
};
