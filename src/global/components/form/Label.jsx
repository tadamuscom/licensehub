/**
 * Add a label
 *
 * @since 1.0.2
 *
 * @param children
 * @param className
 * @param props
 * @returns {JSX.Element}
 * @constructor
 */
export const Label = ({ children, className, ...props }) => {
	const defaultClasses =
		'font-poppins font-semibold text-tadaBlack text-base block cursor-pointer';

	return (
		<label
			className={className ? defaultClasses + ' ' + className : defaultClasses}
			{...props}>
			{children}
		</label>
	);
};
