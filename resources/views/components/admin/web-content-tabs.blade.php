@props(['active' => 'contents'])

<div style="display:flex; gap:8px; border-bottom:1px solid var(--paces-border); margin-bottom:1.5rem; max-width:960px; overflow-x:auto;">
  <a href="{{ route('admin.web.contents') }}" style="padding:0.6rem 1.15rem; font-size:0.88rem; font-weight:{{ $active === 'contents' ? '600' : '500' }}; color:{{ $active === 'contents' ? 'var(--paces-primary, #3e60d5)' : 'var(--paces-text-muted)' }}; text-decoration:none; border-bottom:2px solid {{ $active === 'contents' ? 'var(--paces-primary, #3e60d5)' : 'transparent' }}; display:flex; align-items:center; gap:6px; white-space:nowrap;">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
    <span>Section Content</span>
  </a>
  <a href="{{ route('admin.web.services') }}" style="padding:0.6rem 1.15rem; font-size:0.88rem; font-weight:{{ $active === 'services' ? '600' : '500' }}; color:{{ $active === 'services' ? 'var(--paces-primary, #3e60d5)' : 'var(--paces-text-muted)' }}; text-decoration:none; border-bottom:2px solid {{ $active === 'services' ? 'var(--paces-primary, #3e60d5)' : 'transparent' }}; display:flex; align-items:center; gap:6px; white-space:nowrap;">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>
    <span>Services</span>
  </a>
  <a href="{{ route('admin.web.amc-plans') }}" style="padding:0.6rem 1.15rem; font-size:0.88rem; font-weight:{{ $active === 'amc_plans' ? '600' : '500' }}; color:{{ $active === 'amc_plans' ? 'var(--paces-primary, #3e60d5)' : 'var(--paces-text-muted)' }}; text-decoration:none; border-bottom:2px solid {{ $active === 'amc_plans' ? 'var(--paces-primary, #3e60d5)' : 'transparent' }}; display:flex; align-items:center; gap:6px; white-space:nowrap;">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
    <span>AMC Packages</span>
  </a>
  <a href="{{ route('admin.web.testimonials') }}" style="padding:0.6rem 1.15rem; font-size:0.88rem; font-weight:{{ $active === 'testimonials' ? '600' : '500' }}; color:{{ $active === 'testimonials' ? 'var(--paces-primary, #3e60d5)' : 'var(--paces-text-muted)' }}; text-decoration:none; border-bottom:2px solid {{ $active === 'testimonials' ? 'var(--paces-primary, #3e60d5)' : 'transparent' }}; display:flex; align-items:center; gap:6px; white-space:nowrap;">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
    <span>Client Testimonials</span>
  </a>
  <a href="{{ route('admin.web.faqs') }}" style="padding:0.6rem 1.15rem; font-size:0.88rem; font-weight:{{ $active === 'faqs' ? '600' : '500' }}; color:{{ $active === 'faqs' ? 'var(--paces-primary, #3e60d5)' : 'var(--paces-text-muted)' }}; text-decoration:none; border-bottom:2px solid {{ $active === 'faqs' ? 'var(--paces-primary, #3e60d5)' : 'transparent' }}; display:flex; align-items:center; gap:6px; white-space:nowrap;">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
    <span>FAQs Knowledgebase</span>
  </a>
</div>
