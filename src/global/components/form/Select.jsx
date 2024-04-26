/**
 * Add a select element
 *
 * @since 1.0.2
 *
 * @param className
 * @param options
 * @param props
 * @returns {JSX.Element}
 * @constructor
 */
export const Select = ({ className, options, ...props }) => {
	const defaultClasses = 'tada-select';

	return (
		<select
			className={className ? defaultClasses + ' ' + className : defaultClasses}
			{...props}>
			{options}
		</select>
	);
};
