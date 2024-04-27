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
	const defaultClasses =
		'w-[300px] mt-2 bg-grey-200 border-2 border-black rounded-md p-2 transition-all focus:outline-none focus:shadow-none focus:bg-grey-400 focus:text-black focus:border-black hover:outline-none hover:shadow-none hover:bg-grey-400 hover:text-black hover:border-black disabled:bg-grey-300';

	return (
		<select
			className={className ? defaultClasses + ' ' + className : defaultClasses}
			{...props}>
			{options}
		</select>
	);
};
