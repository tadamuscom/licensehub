/**
 * Add a helper text under inputs
 *
 * @since 1.0.2
 *
 * @param className
 * @param content
 * @param props
 * @returns {JSX.Element}
 * @constructor
 */
export const HelperText = ({ className, content, ...props }) => {
	const defaultClasses = 'font-poppins text-sm text-tadaBlack mt-1 pl-1';

	return (
		<p
			className={className ? defaultClasses + ' ' + className : defaultClasses}
			{...props}>
			{content}
		</p>
	);
};
