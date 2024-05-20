import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { addQueryParameter } from '@global';
import { toastOptions } from '@global';
import classNames from 'classnames';
import ContentEditable from 'react-contenteditable';
import { toast } from 'react-toastify';

export const TableColumn = ({
	data,
	editable,
	column,
	onBlur,
	row,
	updateOriginalValue,
}) => {
	const [columnData, setColumnData] = useState(() => {
		if (column.hidden) return '********';

		return data.value;
	});

	const isEditable = useState(() => {
		if (!column) return false;
		if (!editable) return false;

		return column.editable;
	});

	const isButton = useState(() => {
		if (editable) return false;
		if (!column.button) return false;

		return true;
	});

	const handleChange = (event) => {
		setColumnData(event.target.value);
		updateOriginalValue(row[0].value, column.name, event.target.value);
	};

	const handleButtonClick = () => {
		if (!column.button) return;

		if (column.button === 'edit') addQueryParameter('id', row[0].value);
	};

	if (isEditable[0]) {
		return (
			<td
				// eslint-disable-next-line react/no-unknown-property
				column={column.name}
				className={classNames({
					'border-2 border-red-500': data.error,
				})}>
				<ContentEditable
					html={columnData}
					onChange={handleChange}
					onBlur={onBlur}
				/>
			</td>
		);
	}

	const nonEditable = (
		<>
			{columnData}
			{column.hidden && (
				<button
					className="cursor-pointer bg-transparent border-0 ml-1"
					onClick={() => {
						navigator.clipboard.writeText(data.value);

						toast.success(
							__('Copied to clipboard', 'licensehub'),
							toastOptions,
						);
					}}>
					{copy}
				</button>
			)}
		</>
	);

	if (isButton[0]) {
		return (
			// eslint-disable-next-line react/no-unknown-property
			<td column={column.name}>
				<button
					onClick={handleButtonClick}
					className="bg-transparent border-0 cursor-pointer text-blue-500">
					{nonEditable}
				</button>
			</td>
		);
	}

	// eslint-disable-next-line react/no-unknown-property
	return <td column={column.name}>{nonEditable}</td>;
};

const copy = (
	<svg
		xmlns="http://www.w3.org/2000/svg"
		width="16"
		height="16"
		viewBox="0 0 1024 1024">
		<path d="M768 832a128 128 0 0 1-128 128H192A128 128 0 0 1 64 832V384a128 128 0 0 1 128-128v64a64 64 0 0 0-64 64v448a64 64 0 0 0 64 64h448a64 64 0 0 0 64-64h64z" />
		<path d="M384 128a64 64 0 0 0-64 64v448a64 64 0 0 0 64 64h448a64 64 0 0 0 64-64V192a64 64 0 0 0-64-64H384zm0-64h448a128 128 0 0 1 128 128v448a128 128 0 0 1-128 128H384a128 128 0 0 1-128-128V192A128 128 0 0 1 384 64z" />
	</svg>
);
