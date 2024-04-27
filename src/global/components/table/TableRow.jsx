import { __ } from '@wordpress/i18n';
import { TableColumn } from '@global/components/table/TableColumn';

export const TableRow = ({ columns, editable, onBlur, onDelete }) => {
	return (
		<tr>
			{columns.map((column, index) => (
				<TableColumn
					data={value}
					key={index}
					column={column}
					editable={editable}
					onBlur={onBlur}
				/>
			))}
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
