import React, { forwardRef } from 'react';

const PrintSection = forwardRef<HTMLDivElement>((_, ref) => {
  return (
    <div ref={ref}>
      <h2>This is printable content</h2>
      <p>Everything here will be printed.</p>
    </div>
  );
});

export default PrintSection;
