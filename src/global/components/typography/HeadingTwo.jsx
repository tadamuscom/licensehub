/**
 * Add a H2 element
 *
 * @since 1.0.2
 *
 * @param className
 * @param label
 * @param props
 * @returns {JSX.Element}
 * @constructor
 */
export const HeadingTwo = ({ className, children, ...props }) => {
	const defaultClasses =
		'tada-heading-2 font-kanit font-bold text-3xl border-b-2 my-4 uppercase w-fit';
	return (
		<h2
			className={className ? defaultClasses + ' ' + className : defaultClasses}
			{...props}>
			{children}
		</h2>
	);
};
